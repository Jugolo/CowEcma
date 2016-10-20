var i = 1000;
asset(i <= 1000, true, "i <= 1000 should be true");
asset(i >= 1000, true, "i >= 1000 should be true");
asset(i < 1000, false, "i < 1000 should be false");
asset(i > 1000, false, "i > 1000 should be false");
asset(2 < 1000, true, "2 < 1000 should be true");
