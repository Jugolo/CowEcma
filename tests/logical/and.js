var i = 10;
asset(i == 10 && i != 1, true, "1 shoul be true");
asset(i != 10, false, "i != 10 should be false");
asset(i != 9, true, "i != 9 should be true");
