<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoResource;
use App\Interfaces\VideoRepositoryInterface;
use App\Video;
use Exception;
use Illuminate\Http\Request;

class VideoController extends BaseAPIControllerExtended
{
    public function __construct(VideoRepositoryInterface $repo)
    {
        // parent::__construct($repo, Video::class, 'Video');
        parent::__construct($repo, VideoResource::class, 'Video');
    }

    public function getVideosByUserId(Request $request, $id)
    {
        try {
            $videos = $this->repository->all(['user_id' => $id]);
            return $this->sendResponse(VideoResource::collection($videos), 'Videos retrieved successfully');
            // $user = User::find($id);
            // return $this->sendResponseWithExtraObject(VideoResource::collection($videos), 'user', $user, 'Videos retrieved successfully');
        } catch (Exception $e) {
            return $this->sendError('Error retrieving videos', $e->getMessage());
        }
    }

    public function getVideosByVideoCategoryId(Request $request, $id)
    {
        try {
            $videos = $this->repository->all(['videocategory_id' => $id]);
            return $this->sendResponse(VideoResource::collection($videos), 'Videos retrieved successfully');
            // $user = User::find($id);
            // return $this->sendResponseWithExtraObject(VideoResource::collection($videos), 'user', $user, 'Videos retrieved successfully');
        } catch (Exception $e) {
            return $this->sendError('Error retrieving videos', $e->getMessage());
        }
    }
}
