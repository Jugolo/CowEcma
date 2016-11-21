new Array("1", "2", "3", "4");
asset(new Array(3).length, 3, "Test length failed");
asset(new Array("one", "two", "tree").length, 3, "Test length 2 failed");
asset(new Array().length, 0, "Test length 3 failed");
asset(new Array("1", "2", "3").join(), "1,2,3", "Test join failed");
asset(new Array("Hallo", "super", "world").join(" "), "Hallo super world", "Test join 2 failed");
asset(new Array("1", "2", "3")+"", "1,2,3", "Array toString failed");
asset(new Array('one', 'two', 'three').reverse().join(" "), "three two one", "Test reverse failed");

var ar = new Array();
ar[0] = "Hallo";
ar[1] = "";
ar[2] = "World";
asset(ar.join(" "), "Hallo World", "Array width init failed");
