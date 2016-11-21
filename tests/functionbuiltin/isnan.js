asset(isNaN(NaN), true, "isnan 1 failed");

asset(isNaN(true), false, "isnan 4 failed");
asset(isNaN(null), false, "isnan 5 failed");
asset(isNaN(37), false, "isnan 6 failed");


asset(isNaN("37"), false, "isnan 7 failed");
asset(isNaN("37.37"), false, "isnan 8 failed");
asset(isNaN("123ABC"), true, "isnan 9 failed");
