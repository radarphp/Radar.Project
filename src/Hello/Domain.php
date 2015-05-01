<?php
namespace Example\Hello;

use Aura\Payload\Payload;

class Domain
{
    protected $payload;

    public function __construct(Payload $payload)
    {
        $this->payload = $payload;
    }

    public function __invoke(array $input)
    {
        if (empty($input['name'])) {
            return $this->payload
                ->setStatus(Payload::NOT_VALID)
                ->setExtras([
                    'name' => 'Please pass a name to say hello to.',
                ]);
        }

        return $this->payload
            ->setStatus(Payload::FOUND)
            ->setOutput((object) [
                'phrase' => 'Hello ' . $input['name']
            ]);
    }
}
