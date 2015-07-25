<?php

// Destroy session
session_start();
session_destroy();

// Redirect to index
Header('Location:/LastMileData/');

?>