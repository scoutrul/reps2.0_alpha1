<?php

namespace App\Http\Controllers;

use App\Comment;
use App\ForumSection;
use App\ForumTopic;
use App\Http\Requests\ForumTopicRebaseRequest;
use App\Http\Requests\ForumTopicStoreRequest;
use App\Http\Requests\ForumTopicUpdteRequest;
use App\Services\Forum\TopicService;
use Illuminate\Support\Facades\Auth;

class ForumTopicController extends Controller
{
    /**
     * Display topic page
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory
     */
    public function index($id)
    {
        $topic = ForumTopic::getTopicWithRelations($id);
        if(!$topic){
            return abort(404);
        }
        $comments = Comment::getObjectComments($topic);

        TopicService::updateReview($topic);

        return view('forum.topic')->with([
                'topic' => $topic->load('section'),
                'comments' => $comments
            ]);
    }

    /**
     * Display form for create new topic
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create()
    {
        return view('forum.create_topic')->with('sections', ForumSection::all());
    }

    /**
     * Save new topic and redirect to it
     *
     * @param ForumTopicStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ForumTopicStoreRequest $request)
    {
        return redirect()->route('forum.topic.index', ['id' => TopicService::storeTopic($request)]);
    }

    /**
     * Rebase topic to other section
     *
     * @param ForumTopicRebaseRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rebase(ForumTopicRebaseRequest $request, $id)
    {
        $topic = ForumTopic::find($id);

        if (!$topic){
            return abort(404);
        }

        if ($topic->user_id != Auth::id()){
            return abort(403);
        }

        TopicService::rebaseTopic($request->get('section_id'), $id);
        return redirect()->route('forum.topic.index', ['id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $topic = ForumTopic::where('id', $id)->with('icon')->first();

        if(!$topic){
            return abort(404);
        }

        return view('forum.edit_topic', [
            'topic'     => $topic->load('section'),
            'sections'  => ForumSection::where('is_active', 1)->get(['id', 'title','name'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ForumTopicUpdteRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ForumTopicUpdteRequest $request, $id)
    {
        $topic = ForumTopic::find($id);
        if (!$topic){
            return abort(404);
        }

        TopicService::update($request, $topic);
        return redirect()->route('forum.topic.index', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $topic = ForumTopic::find($id);

        if (!$topic){
            return abort(404);
        }

        if ($topic->user_id != Auth::id()){
            return abort(403);
        }

        TopicService::remove($topic);

        return redirect()->route('forum.index');
    }

    /**+
     * @param int $user_id
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function getUserTopic($user_id = 0)
    {
        if ($user_id == 0){
            $user_id = Auth::id();
        }

        $data = ForumSection::getUserTopics($user_id);
        return view('forum.my_topics')->with('topics', $data);
    }
}
