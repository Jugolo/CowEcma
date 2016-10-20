var i = 1000;
asset(i == 10, false, "i == 10 should be false");
asset(i == 1000, true, "i == 1000 should be true");
asset(i != 1000, false, "i != 1000 should be false");
asset(i != 10, true, "i != 10 should be true");
