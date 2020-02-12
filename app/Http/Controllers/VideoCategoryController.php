<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoCategoryResource;
use App\Interfaces\VideoCategoryRepositoryInterface;
use Exception;
use Illuminate\Http\Request;

class VideoCategoryController extends BaseAPIControllerExtended
{
    public function __construct(VideoCategoryRepositoryInterface $repo)
    {
        parent::__construct($repo, VideoCategoryResource::class, 'VideoCategory');
    }

    public function getVideoCategoriesByUserId(Request $request, $id)
    {
        try {
            $videoCategories = $this->repository->all(['user_id' => $id]);
            return $this->sendResponse(VideoCategoryResource::collection($videoCategories), 'Video Categories retrieved successfully');
            // $user = User::find($id);
            // return $this->sendResponseWithExtraObject(VideoResource::collection($videos), 'user', $user, 'Videos retrieved successfully');
        } catch (Exception $e) {
            return $this->sendError('Error retrieving video categories', $e->getMessage());
        }
    }
}
