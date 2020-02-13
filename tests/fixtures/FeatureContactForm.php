<?php

class FeatureContactForm
{
	public function scenarioProvidingValidInput(): array
	{
		return [
			"Given that I visit the contact page",
			"When I fill in the name and message field and submit",
			"Then I should get a confirmation message"
		];
	}


	public function scenarioProvidingInvalidInput(): array
	{
		return [
			"Given that I visit the contact page",
			"When I fill in the message but no name and submit",
			"Then I should get an error message"
		];
	}
}
