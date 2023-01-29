<?php
/**
 * Created by PhpStorm.
 * User: szyman
 * Date: 20.12.18
 * Time: 19:12
 */

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use File;

class AvatarUploader
{
    private $disk;

    public function __construct()
    {
        $this->disk = Storage::disk('public');
    }

    public function upload(User $user, $image)
    {
        DB::beginTransaction();

        // Upload new avatar
        $file = $this->makeImage($image);
        $path = $this->uploadImage($user, $file);

        // If user has avatar
        $this->removeOldAvatar($user);

        // Update database
        $user = $this->updateUser($user, $path);

        DB::commit();

        return $user->avatar;
    }

    private function makeImage($image)
    {
        $file = Image::make($image);

        $file->fit(800, 800, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $file;
    }

    private function uploadImage(User $user, $file)
    {
        $userId = $user->id;
        $fileName = time() . ".png";
        $imagePath = "images/user/{$userId}/$fileName";
        $this->disk->put($imagePath, (string) $file->encode());

        return '/storage/' . $imagePath;
    }

    private function removeOldAvatar(User $user)
    {
        if (isset($user->dataList['avatar'])) {
            $currentAvatar = $user->dataList['avatar'];
            $path = str_replace('storage', 'public', $currentAvatar);
            if (Storage::exists($path)) {
                // Remove existing avatar
                Storage::delete($path);
            }
        }
    }

    private function updateUser(User $user, $path)
    {
        $user->data()->updateOrCreate([
            'code' => 'avatar'
        ], [
            'value' => $path
        ]);

        return $user;
    }
}