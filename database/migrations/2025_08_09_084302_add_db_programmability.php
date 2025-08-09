<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
-- =========================
-- FUNCTIONS (3)
-- =========================
CREATE OR REPLACE FUNCTION fn_property_avg_rating(p_property_id BIGINT)
RETURNS NUMERIC AS $$
DECLARE avg_val NUMERIC;
BEGIN
  SELECT ROUND(AVG(rating)::numeric, 2) INTO avg_val FROM reviews WHERE property_id = p_property_id;
  RETURN COALESCE(avg_val, 0.0);
END; $$ LANGUAGE plpgsql STABLE;

CREATE OR REPLACE FUNCTION fn_reservation_total(p_reservation_id BIGINT)
RETURNS NUMERIC AS $$
DECLARE total NUMERIC;
BEGIN
  SELECT fee INTO total FROM reservations WHERE id = p_reservation_id;
  RETURN COALESCE(total, 0);
END; $$ LANGUAGE plpgsql STABLE;

CREATE OR REPLACE FUNCTION fn_user_full_name(p_user_id BIGINT)
RETURNS TEXT AS $$
DECLARE n TEXT;
BEGIN
  SELECT name INTO n FROM users WHERE id = p_user_id;
  RETURN COALESCE(n, '');
END; $$ LANGUAGE plpgsql STABLE;

-- =========================
-- TRIGGERS (2)
-- =========================
-- A) Prevent double-booking and mark property unavailable
CREATE OR REPLACE FUNCTION trg_check_and_mark_reserved()
RETURNS TRIGGER AS $$
BEGIN
  IF (SELECT is_available FROM properties WHERE id = NEW.property_id) IS DISTINCT FROM TRUE THEN
    RAISE EXCEPTION 'Property is not available for reservation.';
  END IF;

  UPDATE properties SET is_available = FALSE, updated_at = NOW()
  WHERE id = NEW.property_id;

  RETURN NEW;
END; $$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS before_insert_reservations ON reservations;
CREATE TRIGGER before_insert_reservations
BEFORE INSERT ON reservations
FOR EACH ROW EXECUTE FUNCTION trg_check_and_mark_reserved();

-- B) Refresh cached avg_rating on properties when reviews change
CREATE OR REPLACE FUNCTION trg_refresh_property_avg_rating()
RETURNS TRIGGER AS $$
DECLARE pid BIGINT;
BEGIN
  pid := COALESCE(NEW.property_id, OLD.property_id);
  UPDATE properties
     SET avg_rating = fn_property_avg_rating(pid), updated_at = NOW()
   WHERE id = pid;
  RETURN COALESCE(NEW, OLD);
END; $$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS after_review_change ON reviews;
CREATE TRIGGER after_review_change
AFTER INSERT OR UPDATE OR DELETE ON reviews
FOR EACH ROW EXECUTE FUNCTION trg_refresh_property_avg_rating();

-- =========================
-- STORED PROCEDURES (2)
-- =========================
CREATE OR REPLACE PROCEDURE sp_make_reservation(p_buyer_id BIGINT, p_property_id BIGINT, p_fee NUMERIC)
LANGUAGE plpgsql AS $$
DECLARE v_reservation_id BIGINT;
BEGIN
  PERFORM pg_advisory_xact_lock(1);
  IF p_fee IS NULL OR p_fee < 0 THEN RAISE EXCEPTION 'Invalid reservation fee'; END IF;

  INSERT INTO reservations(property_id, buyer_id, fee, status, reserved_at)
  VALUES (p_property_id, p_buyer_id, p_fee, 'pending', NOW())
  RETURNING id INTO v_reservation_id;

  INSERT INTO payments(reservation_id, amount, method, status, paid_at, created_at, updated_at)
  VALUES (v_reservation_id, p_fee, 'card', 'pending', NULL, NOW(), NOW());

  INSERT INTO invoices(reservation_id, total, pdf_path, issued_at)
  VALUES (v_reservation_id, p_fee, NULL, NOW());
END; $$;

CREATE OR REPLACE PROCEDURE sp_cancel_reservation(p_reservation_id BIGINT)
LANGUAGE plpgsql AS $$
DECLARE v_property BIGINT;
BEGIN
  PERFORM pg_advisory_xact_lock(2);
  SELECT property_id INTO v_property FROM reservations WHERE id = p_reservation_id;

  UPDATE reservations SET status = 'canceled' WHERE id = p_reservation_id;

  IF NOT EXISTS (
    SELECT 1 FROM reservations
     WHERE property_id = v_property
       AND status IN ('pending','paid','completed')
  ) THEN
    UPDATE properties SET is_available = TRUE, updated_at = NOW() WHERE id = v_property;
  END IF;
END; $$;
SQL);
    }

    public function down(): void
    {
        DB::unprepared(<<<'SQL'
DROP PROCEDURE IF EXISTS sp_cancel_reservation(BIGINT);
DROP PROCEDURE IF EXISTS sp_make_reservation(BIGINT, BIGINT, NUMERIC);
DROP TRIGGER IF EXISTS after_review_change ON reviews;
DROP FUNCTION IF EXISTS trg_refresh_property_avg_rating();
DROP TRIGGER IF EXISTS before_insert_reservations ON reservations;
DROP FUNCTION IF EXISTS trg_check_and_mark_reserved();
DROP FUNCTION IF EXISTS fn_user_full_name(BIGINT);
DROP FUNCTION IF EXISTS fn_reservation_total(BIGINT);
DROP FUNCTION IF EXISTS fn_property_avg_rating(BIGINT);
SQL);
    }
};
