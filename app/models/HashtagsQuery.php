<?php

class HashtagsQuery extends Eloquent {

	protected $table = 'hashtags';

	public function getCaseTags($case_id)
    {
        return $this->select('tag')
        ->join('case_hashtags', 'hashtags.id', '=', 'case_hashtags.hashtag_id')
        ->where('case_hashtags.case_id', '=', $case_id)
        ->get();
    }

	public function findHashtags($query)
    {
        return $this
        ->where('hashtags.tag', 'LIKE', '%' . $query . '%')
        ->lists('tag','id');
    }
}
