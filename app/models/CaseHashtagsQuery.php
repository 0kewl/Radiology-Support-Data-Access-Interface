<?php

class CaseHashtagsQuery extends Eloquent {

	protected $table = 'case_hashtags';

	public function getCasesByTag($tag)
    {
        return $this->select('case_id')
        ->join('hashtags', 'hashtags.id', '=', 'case_hashtags.hashtag_id')
        ->where('hashtags.tag', '=', $tag)
        ->get();
    }
}
