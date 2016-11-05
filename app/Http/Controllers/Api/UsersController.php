<?php


namespace App\Http\Controllers\Api;


use App\JsonApi\Users\Hydrator;
use App\JsonApi\Users\Request;
use App\Models\User\User;
use CloudCreativity\LaravelJsonApi\Search\SearchAll;

class UsersController extends BaseController
{

    /**
     * UsersController constructor.
     * @param Hydrator $hydrator
     * @param SearchAll $search
     */
    public function __construct(Hydrator $hydrator, SearchAll $search)
    {
        parent::__construct(new User(), $hydrator, $search);
    }

    // TODO We need to add tests for that before uncomment
//    /**
//     * @inheritdoc
//     */
//    public function replaceRelationship(JsonApiRequest $request)
//    {
//        /** @var User $user */
//        $user = $request->getRecord();
//
//        $ids = $this->getIdsFromRelationshipRequest($request, 'groups');
//
//        if (count($ids) > 0) {
//            $this->transaction(function () use ($ids, $user, $request) {
//                $update = array_combine($ids, array_fill(0, count($ids), [
//                    'is_admin' => $request->getRelationshipName() == 'groups-member' ? false : true
//                ]));
//                $user->groups()->sync($update);
//            });
//        }
//
//        return $this->reply()->relationship($user->{$this->keyForRelationship($request->getRelationshipName())});
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function addToRelationship(JsonApiRequest $request)
//    {
//        /** @var User $user */
//        $user = $request->getRecord();
//
//        $ids = $this->getIdsFromRelationshipRequest($request, 'groups');
//
//        if (count($ids) > 0) {
//            $this->transaction(function () use ($ids, $user, $request) {
//                $update = array_combine($ids, array_fill(0, count($ids), [
//                    'is_admin' => $request->getRelationshipName() == 'groups-member' ? false : true
//                ]));
//                $user->groups()->syncWithoutDetaching($update);
//            });
//        }
//
//        return $this->reply()->relationship($user->{$this->keyForRelationship($request->getRelationshipName())});
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function removeFromRelationship(JsonApiRequest $request)
//    {
//        /** @var User $user */
//        $user = $request->getRecord();
//
//        $ids = $this->getIdsFromRelationshipRequest($request, 'groups');
//
//        if (count($ids) > 0) {
//            $this->transaction(function () use ($ids, $user, $request) {
//                $user->groups()->detach($ids);
//            });
//        }
//
//        return $this->reply()->relationship($user->{$this->keyForRelationship($request->getRelationshipName())});
//    }

    /**
     * @inheritdoc
     */
    protected function getRequestHandler()
    {
        return Request::class;
    }


}