<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait VoteTrait
{
    protected $voteRelation = __CLASS__;
    /**
     * 点赞
     * @param $item
     * @return mixed
     */
    public function upVote($item)
    {
        $this->cancelVote($item);

        return $this->vote($item);
    }

    /**
     * 点赞实现
     * @param $item
     * @return mixed
     */
    protected function vote($item)
    {
        $items = (array) $this->checkVoteItem($item);

        return $this->votedItems()->sync($items , false );
    }

    /**
     * 取消点赞
     * @param int|array|\Illuminate\Database\Eloquent\Model $item
     *
     * @return int
     */
    public function cancelVote($item)
    {
        $item = $this->checkVoteItem($item);

        return $this->votedItems()->detach((array)$item);
    }

    /**
     * 检查是否点赞
     * @param $item
     *
     * @return bool
     */
    public function hasVoted($item)
    {
        $item = $this->checkVoteItem($item);

        $votedItems = $this->votedItems();

        return $votedItems->get()->contains($item);
    }

    /**
     * Set the relationships
     * @param null $class
     * @return mixed
     */
    public function votedItems($class = null )
    {
        if (!empty($class)) {
            $this->setVoteRelation($class);
        }

        return $this->morphedByMany( $this->voteRelation ,
            'votable',
            'votes',
            'user_id',
            'votable_id')
            ->withTimestamps('created_at','updated_at');
    }

    /**
     * Determine whether $item is an instantiated object of \Illuminate\Database\Eloquent\Model
     *
     * @param $item
     *
     * @return int
     */
    protected function checkVoteItem($item)
    {
        if ($item instanceof Model) {
            $this->setVoteRelation(get_class($item));
            return $item->id;
        };
        return $item;
    }
    /**
     * Set the vote relation class.
     *
     * @param string $class
     */
    protected function setVoteRelation($class)
    {
        return $this->voteRelation = $class;
    }


}