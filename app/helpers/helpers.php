<?php
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CandidateController;


// function to save image
function saveImage($request)
{
    $image = $request->file('category_image');
    $imageName = rand() . '.' . $image->getClientOriginalExtension();
    $directory = 'adminAsset/category-image/';
    $imgUrl = $directory . $imageName;
    $image->move($directory, $imageName);
    return $imgUrl;
}

// Function to remove an image
function removeImage($imagePath)
{
    // Check if the file exists before attempting to delete it
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

function candidateSaveImage($request)
{
//    $file = $request->file($fileType);
    $image = $request->file('image');
    $imageName = rand() . '.' . $image->getClientOriginalExtension();
    $directory = 'Asset/candidate-image/';
    $imgUrl = $directory . $imageName;
    $image->move($directory, $imageName);
    return $imgUrl;
}

function candidateRemoveImage($imagePath)
{
    // Check if the file exists before attempting to delete it
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

function saveResume($request)
{
    $image = $request->file('resume');
    $imageName = rand() . '.' . $image->getClientOriginalExtension();
    $directory = 'adminAsset/resume/';
    $imgUrl = $directory . $imageName;
    $image->move($directory, $imageName);
    return $imgUrl;
}
