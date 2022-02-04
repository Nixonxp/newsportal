<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param $ability
     * @return bool|void
     */
    public function before(User $user, $ability)
    {
        if ($user->hasAnyRole(['admin'])) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        if ($user->hasAnyRole(['admin','Chief-editor', 'Editor'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Post $post
     * @return Response|bool
     */
    public function view(User $user, Post $post)
    {
        if ($user->hasAnyRole(['admin','Chief-editor'])) {
            return true;
        }

        return ($post->user_id == $user->id) || ($post->is_published === true);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return Response|bool
     */
    public function create(User $user)
    {
        return $user->hasAnyRole(['admin','Chief-editor','Editor']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Post $post
     * @return Response|bool
     */
    public function update(User $user, Post $post)
    {
        if ($user->hasAnyRole(['admin','Chief-editor'])) {
            return true;
        }

        return $post->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Post $post
     * @return Response|bool
     */
    public function delete(User $user, Post $post)
    {
        if ($user->hasAnyRole(['admin','Chief-editor'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Post $post
     * @return Response|bool
     */
    public function restore(User $user, Post $post)
    {
        if ($user->hasAnyRole(['admin','Chief-editor'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Post $post
     * @return Response|bool
     */
    public function forceDelete(User $user, Post $post)
    {
        if ($user->hasAnyRole(['admin','Chief-editor'])) {
            return true;
        }

        return false;
    }
}
