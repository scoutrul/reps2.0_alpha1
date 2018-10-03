<?php

namespace App\Http\Controllers;

use App\CensorshipWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\ForumTopicComment;
use App\Http\Requests\CommentUpdateRequest;
use App\ReplayComment;

class CommentController extends Controller
{
    /**
     * Model name
     *
     * @var string
     */
    protected static $model;

    /**
     * View name
     *
     * @var string
     */
    protected static $view_name;

    /**
     * object name with 'id'
     *
     * @var string
     */
    protected static $name_id;

    /**
     * Update the specified resource in storage.
     *
     * @param CommentUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CommentUpdateRequest $request, $id)
    {
        if (self::$model::find($id)){
            self::updateComment($request, $id);
            return redirect()->route(self::$view_name, ['id' => $id]);
        }

        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $object = self::$model::find($id);

        $name_id = self::$name_id;
        $object_id = $object->$name_id;

        if (!$object){
            return abort(404);
        }

        if ($object->user_id != Auth::id()){
            return abort(403);
        }

        $object->delete();

        return redirect()->route(self::$view_name, ['id' => $object_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     */
    public static function storeComment(Request $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $data = self::checkCommentData($data);

        self::$model::create($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     */
    public static function updateComment(Request $request, $id)
    {
         $replay_data = $request->validated();
         $replay_data['title'] = $replay_data['title']??null;

         $replay_data = self::checkCommentData($replay_data);

         self::$model::where('id', $id)->update($replay_data);
    }

    /**
     * Check text data in comment to censorship words
     *
     * @param array $data
     * @return array
     */
    public static function checkCommentData(array $data)
    {
        if(isset($data['title']) && $data['title']){
            $data['title'] = CensorshipWord::check($data['title']);
        }

        $data['comment'] = CensorshipWord::check($data['comment']);

        return $data;
    }
}
