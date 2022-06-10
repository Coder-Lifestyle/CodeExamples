<?php

namespace App\Service\API\Email\GuerrillaMail;

use GuzzleHttp\Exception\GuzzleException;

class GuerrillaMailClient
{
    /**
     * @var GuerrillaMailConnection|null
     */
    private $client = null;

    /**
     * @var string
     */
    private $sidToken = null;

    /**
     * @param GuerrillaMailConnection $client
     * @param null $sidToken
     */
    public function __construct(GuerrillaMailConnection $client, $sidToken = null)
    {
        $this->client = $client;
        $this->sidToken = $sidToken;
    }

    /**
     * @param string $sidToken
     */
    public function setSidToken($sidToken)
    {
        $this->sidToken = $sidToken;
    }

    /**
     * @return string
     */
    public function getSidToken()
    {
        return $this->sidToken;
    }

    /**
     * Fetch new email address or
     * resume previous state if $this->sid_token != NULL
     *
     * @param string $lang
     * @return mixed
     * @throws GuzzleException
     */
    public function getEmailAddress($lang = 'en')
    {
        $action = "get_email_address";
        $options = array(
            'lang' => $lang,
            'sid_token' => $this->sidToken,
        );

        return $this->client->request('GET', $action, $options);
    }

    /**
     * Fetch up to 20 new emails starting from the oldest email.
     * If $seq is set, return up to 20 new emails starting from $seq
     *
     * @param int $seq mail_id sequence number starting point
     * @return mixed
     * @throws GuzzleException
     */
    public function checkEmail($seq = 0)
    {
        $action = "check_email";
        $options = array(
            'seq' => $seq,
            'sid_token' => $this->sidToken
        );

        return $this->client->request('GET', $action, $options);
    }

    /**
     * Fetch up to 20 new emails starting from the oldest email.
     * If $offset is set, skip to the offset value (0 - 19)
     * If $seq is set, return up to 20 new emails starting from $seq
     *
     * @param int $offset number of items to skip (0 - 19)
     * @param int $seq mail_id sequence number starting point
     * @return mixed
     * @throws GuzzleException
     */
    public function getEmailList($offset = 0, $seq = 0)
    {
        $action = "get_email_list";
        $options = array(
            'offset' => $offset,
            'sid_token' => $this->sidToken
        );

        if(!empty($seq))
        {
            $options['seq'] = $seq;
        }

        return $this->client->request('GET', $action, $options);
    }

    /**
     * Return email based on $email_id
     *
     * @param int $email_id email id of the requested email
     * @return bool
     * @throws GuzzleException
     */
    public function fetchEmail($email_id)
    {
        $action = "fetch_email";
        $options = array(
            'email_id' => $email_id,
            'sid_token' => $this->sidToken
        );

        return $this->client->request('GET', $action, $options);
    }

    /**
     * Change users email address
     *
     * @param $email_user
     * @param string $lang
     * @return bool
     * @throws GuzzleException
     */
    public function setEmailAddress($email_user, $lang = 'en')
    {
        $action = "set_email_user";
        $options = array(
            'email_user' => $email_user,
            'lang' => $lang,
            'sid_token' => $this->sidToken
        );

        return $this->client->request('POST', $action, $options);
    }

    /**
     * Forget users email and sid_token
     *
     * @param $email_address
     * @return bool
     * @throws GuzzleException
     */
    public function forgetMe($email_address)
    {
        $action = "forget_me";
        $options = array(
            'email_addr' => $email_address,
            'sid_token' => $this->sidToken
        );

        return $this->client->request('POST', $action, $options);
    }

    /**
     * Delete the emails matching the array of mail_id's in $email_ids
     * @param array $email_ids list of mail_ids to delete from the server.
     * @return bool
     * @throws GuzzleException
     */
    public function delEmail($email_ids)
    {
        $action = "del_email";
        $options = array(
            'email_ids' => $email_ids,
            'sid_token' => $this->sidToken
        );

        return $this->client->request('POST', $action, $options);
    }
}
