asset(unescape("abc123"), "abc123", "unescape 1 fail");
asset(unescape("%E4%F6%FC"), "äöü", "unescape 2 fail");
asset(unescape("%u0107"), "ć",      "unescape 3 fail");
asset(unescape("@*_+-./"), "@*_+-./", "unescape 4 fail");
