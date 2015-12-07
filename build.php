<?php
$srcRoot = "src";
$buildRoot = "build";
$vendor = "vendor";

echo "Building Sample React Project...\n";

function delTree($dir) { 
    $files = array_diff(scandir($dir), array('.','..')); 
    foreach ($files as $file) { 
      (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file"); 
    } 
    return rmdir($dir); 
}

function copyFiles($dir, $buildRoot, $srcRoot, $isConfig = FALSE) { 
    if($isConfig){
        echo "Creating " . $buildRoot . "/" . $dir . " ..\n";
        mkdir($buildRoot . "/" . $dir);
        $files = glob($dir . "/*.*");

        foreach($files as $file){
            $file_to_go = str_replace($dir , $buildRoot . "/config", $file);
            copy($file, $file_to_go);
        }
    }else{
        echo "Creating " . $buildRoot . "/sample/" . $dir . " ..\n";
        mkdir($buildRoot . "/sample/" . $dir);
        $files = glob($srcRoot . "/" . $dir . "/*.*");

        foreach($files as $file){
            $file_to_go = str_replace($srcRoot , $buildRoot . "/sample", $file);
            copy($file, $file_to_go);
        }
    }
}


// Delete the required directories if they exist.
if(file_exists($buildRoot . "/sample")){
	echo "Deleting " . $buildRoot . "/sample ..\n";
	delTree($buildRoot . "/sample");
}
    
mkdir($buildRoot . "/sample");

//Create the required directories and files for the web application.
copyFiles("common", $buildRoot, $srcRoot);
copyFiles("controllers", $buildRoot, $srcRoot);
copyFiles("public", $buildRoot, $srcRoot);

// Copy composer.json file_exists
copy($buildRoot . "/composer.json", $buildRoot . "/sample/composer.json");

//copy config files.
if(file_exists($buildRoot . "/config")){
	echo "Deleting " . $buildRoot . "/config ..\n";
	delTree($buildRoot . "/config");
}

copyFiles("config", $buildRoot, $srcRoot, TRUE);

// Delete vendor directory if it exists.
if(file_exists($buildRoot . "/sample/vendor")){
	echo "Deleting " . $buildRoot . "/sample/vendor...\n";
	delTree($buildRoot . "/sample/vendor");
}

// Execute composer install if vendor directory does not exist.
if(file_exists($buildRoot . "/sample/vendor")){
	echo "Deleting " . $buildRoot . "/sample/vendor...\n";
	delTree($buildRoot . "/sample/vendor");
}

echo "Executing Composer Install...\n";
$output = `composer -d=build/sample install`;
echo $output;
echo "Build Successfull!\n";
exit(1);