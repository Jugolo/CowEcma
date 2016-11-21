asset(escape("abc123"), "abc123", "escape 1 fail");
asset(escape("äöü"), "%E4%F6%FC", "escape 2 fail");
asset(escape("ć"), "%u0107",      "escape 3 fail");
asset(escape("@*_+-./"), "@*_+-./", "escape 4 fail");
