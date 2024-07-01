<?php namespace App\models;

require_once "../backend/common/models/ModelApp.php";
require_once "../backend/config/db/Connect.php";

use app\common\models\ModelApp;
use app\config\db\Connect;
use PDO;

class TestAlfa extends ModelApp
{
    public int|null $id;
    public string $site = '';
    public string $time_live = '';
    public float $scroll = 0.00;
    public string $history_clicks = '{}';
    public string $browser = '';
    public string $device = '';
    public string $platform = '';
    public string $ip = '';
    public string $cookie_id = '';
    public string $user_agent = '';

    protected array $props = [
        'site',
        'time_live',
        'scroll',
        'history_clicks',
        'browser',
        'device',
        'platform',
        'ip',
        'cookie_id',
        'user_agent',
    ];

    public function getAll(): array|false
    {
        $connect = new Connect();
        return $connect->connection->query('SELECT * FROM ts.test_alfa')->fetch(PDO::FETCH_ASSOC);
    }

    public function getOne(int $id): ?self
    {
        $connect = new Connect();
        $row = $connect->connection->query('SELECT * FROM ts.test_alfa WHERE id = ' . $id . ' LIMIT 1')->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            $this->setError(get_called_class(), "По id - " . $id . " запись не найдена");

            return null;
        }

        $this->id = $row['id'];
        foreach ($this->props as $prop) {
            $this->$prop = $row[$prop];
        }

        return $this;
    }

    protected function isUniqueUser(array $userIdentifiers): int
    {
        $connect = new Connect();

        if(isset($userIdentifiers['ip']) && isset($userIdentifiers['cookie_id'])) {
            $sql = "SELECT id FROM ts.test_alfa WHERE ip = '" . $userIdentifiers['ip'] . "' AND cookie_id = '" . $userIdentifiers['cookie_id'] . "' LIMIT 1";
            $query = $connect->connection->query($sql)->fetch(PDO::FETCH_ASSOC);

            if (!empty($query)) {

                return (int)$query['id'];
            }

            return 0;
        }
        $this->setError(get_called_class(), 'Не все необходимые параметры заполнены');

        return 0;
    }

    public function create(): int
    {
        $connect = new Connect();
        try {
            $str_nameProps = implode(', ', $this->props);

            $stmt = $connect->connection->prepare(
                "INSERT INTO ts.test_alfa (" . $str_nameProps . ") VALUES (:site, :time_live, :scroll, :history_clicks, :browser, :device, :platform, :ip, :cookie_id, :user_agent)"
            );
            foreach ($this->props as $prop) {
                $stmt->bindParam(':'.$prop, $this->$prop);
            }

            if ($stmt->execute()) {

                return $connect->connection->lastInsertId();
            }
            $this->setError(get_called_class(), "Запрос не выполнился");

            return 0;
        } catch (\PDOException $exception) {
            $this->setError(get_called_class(), $exception->getMessage());

            return 0;
        }
    }

    public function update(int $user_id): bool
    {
        $connect = new Connect();
        try {
            $conditions = $this->prepareConditionsUpdate();

            $currentTimeLive = $this->time_live;
            $currentScroll = $this->scroll;

            $newHistoryClicks = $this->prepareHistoryClicks($user_id);

            if (!empty($conditions)) {
                $sql = "UPDATE ts.test_alfa
                        SET time_live=:time_live, scroll=:scroll, history_clicks=:newHistoryClicks, browser=:browser, device=:device, platform=:platform, user_agent=:user_agent
                        WHERE " . $conditions;

                $stmt = $connect->connection->prepare($sql);

                $stmt->bindParam(':time_live', $currentTimeLive);
                $stmt->bindParam(':scroll', $currentScroll);
                $stmt->bindParam(':newHistoryClicks', $newHistoryClicks);
                $stmt->bindParam(':browser', $this->browser);
                $stmt->bindParam(':device', $this->device);
                $stmt->bindParam(':platform', $this->platform);
                $stmt->bindParam(':user_agent', $this->user_agent);

                return $stmt->execute();
            }

            return false;
        } catch (\PDOException $exception) {
            $this->setError(get_called_class(), $exception->getMessage());

            return false;
        }
    }

    private function prepareConditionsUpdate(): string
    {
        if(isset($this->ip) && isset($this->cookie_id)) {
            $userIdentifiers = ['ip' => $this->ip, 'cookie_id' => $this->cookie_id];

            $condition = [];
            foreach ($userIdentifiers as $key => $value) {
                $condition[] = $key . " = '" . $value . "'";
            }

            return implode(" AND ", $condition);
        }
        $this->setError(get_called_class(), 'Не все необходимые параметры заполнены');

        return '';
    }

    private function prepareHistoryClicks(int $id): string
    {
        if (isset($this->history_clicks)) {
            $currentHistoryClicks = json_decode($this->history_clicks, JSON_OBJECT_AS_ARRAY);
            $user_db = $this->getOne($id);

            $history_clicks_db = json_decode($user_db->history_clicks, JSON_OBJECT_AS_ARRAY);

            $historyClicks = array_merge($history_clicks_db, $currentHistoryClicks);

            return json_encode($historyClicks, JSON_FORCE_OBJECT);
        }
        $this->setError(get_called_class(), 'Не все необходимые параметры заполнены');

        return '';
    }
}