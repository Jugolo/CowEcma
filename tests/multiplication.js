var i = 10;
i = i / 1;
asset(i, 10, "i should be 10");
i = i * 10;
asset(i, 100, "i should be 100");
i = i % 2;
asset(i, 0, "i should be 10");
