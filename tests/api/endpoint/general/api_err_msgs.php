<?php

use JsonSchema\Validator;
use classes\APITestCase;
use classes\APITestUtils;

class api_err_msgs extends APITestCase {
	use traits\TestIsResponseCode200;
	use traits\TestIsResponseContentTypeJSON;

	public function setUp(): void {
		$this->set_endpoint_method('GET');
		$this->set_endpoint_uri('general/api_err_msgs.php');
		parent::setUp();
	}

	public function test_is_response_output_schema_correct(): void {
		$resp = $this->api->call($this->get_endpoint_method(), $this->get_endpoint_uri());

		$validator = new Validator();
		$validator->validate(
			$resp,
			[
				'type' => 'object',
				'properties' => [
					'messages' => [
						'type' => 'array',
						'items' => [
							'type' => 'object',
							'properties' => [
								'short' => ['type' => 'string'],
								'long' => ['type' => 'string']
							],
							'required' => ['short', 'long'],
							'additionalProperties' => FALSE
						]
					],
					'error' => ['type' => 'integer'],
					'required' => ['messages', 'error'],
					'additionalProperties' => FALSE
				],
				'required' => ['messages', 'error'],
				'additionalProperties' => FALSE
			]
		);
		$this->assertEquals(
			TRUE,
			$validator->isValid(),
			APITestUtils::json_schema_error_string($validator)
		);
	}
}
