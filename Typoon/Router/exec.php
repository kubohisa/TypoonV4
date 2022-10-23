<?php

    urlFunc("/", "index");

    urlFunc("/test/:id", "test");

    urlFunc("/wildcard/*id", "test");
