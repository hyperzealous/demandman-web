<?php

/**
 * Defines the message handling for the API.
 */
namespace App\Services;

use App\Model\Appliance;
use App\ActionRequest;
use App\ActionResponse;
use App\Model\AppActionRequest;
use App\Model\AppActionResponse;
use App\Events\AppActionRequestEvent;
use App\Events\AppActionResponseEvent;

use Carbon\Carbon;
use Event;
use Redis;

/**
 * Defines the message handling for the API.
 */
class EventMessenger implements ApiMessenger
{
	/**
	 * Broadcast an ActionRequest to subscribers.
	 *
	 * @param \App\ActionRequest $request The request to broadcast.
	 * @return void
	 */
	public function broadcastRequest(ActionRequest $request) {
		Event::fire(new AppActionRequestEvent($request));
	}

	/**
	 * Create an ActionRequest instance.
	 *
	 * @param \App\Model\Appliance $app The appliance under request.
	 * @param string $action The requested action.
	 * @return \App\ActionRequest A new ActionRequest instance.
	 */
	public function createRequest(Appliance $app, $action) {
		$redis = Redis::connection('pubsub');
		$actionRequest = new AppActionRequest();
		$actionRequest->appId = $app->id;
		$actionRequest->action = $action;
		$actionRequest->started_at = Carbon::parse($redis->get('simulation:time'));
		$actionRequest->save();

		return $actionRequest;
	}

	/**
	 * Encode an ActionRequest as a string.
	 *
	 * @param \App\ActionRequest $request The ActionRequest to encode.
	 * @return string A string representation of the request.
	 */
	public function encodeRequest(ActionRequest $request) {
	}

	/**
	 * Decode a request string into an ActionRequest object.
	 *
	 * @param string $request The request string to decode.
	 * @return \App\ActionRequest The decoded ActionRequest object.
	 */
	public function decodeRequest($request) {
	}

	public function broadcastComplete(ActionRequest $request, AppActionResponse $response) {
		$appId = $response->appId;
		$action = $response->action;
		$data = json_encode([ "data" => [ "actionComplete" => [
			"status" => $response->status,
			"appId" => $appId,
			"action" => $action,
			"requestId" => $request->id]]]);
		$redis = Redis::connection("pubsub");
		$redis->publish("dm.complete.appliance.$appId.action.$action", $data);
	}

	/**
	 * Broadcast an ActionResponse to subscribers.
	 *
	 * @param \App\ActionResponse $response The response to broadcast.
	 * @return void
	 */
	public function broadcastResponse(ActionResponse $response) {
		Event::fire(new AppActionResponseEvent($response));
	}

	/**
	 * Create an ActionResponse instance.
	 *
	 * @param \App\ActionRequest $request The request to respond to.
	 * @param mixed $response The contents of the response.
	 * @return \App\ActionResponse A new ActionResponse object.
	 */
	public function createResponse(ActionRequest $request, $response) {
		$actionResponse = new AppActionResponse();
		$actionResponse->appId = $request->appId;
		$actionResponse->action = $request->getAction();
		$actionResponse->request = $request;
		$data = $this->decodeResponse($response);
		$actionResponse->status = $data['status'];
		//more stuff

		return $actionResponse;
	}

	/**
	 * Encode an ActionResponse as a string.
	 *
	 * @param \App\ActionResponse $response The ActionResponse to encode.
	 * @return string A string representation of the response.
	 */
	public function encodeResponse(ActionResponse $response) {
	}

	/**
	 * Decode a response string into an ActionResponse object.
	 *
	 * @param string $response The response string to decode.
	 * @return \App\ActionResponse The decoded ActionResponse object.
	 */
	public function decodeResponse($response) {
		return json_decode($response, true)['data']['actionResponse'];
	}
}
