<?php
// tools/ingest_votes_oct2025.php
// Загрузка тестовых голосов за октябрь 2025 в локальную БД через VoteService

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../src/services/VoteService.php';

$content = <<<TXT
283316526	01.10.2025 00:10:30	46.42.148.108	cool	1
283319763	01.10.2025 02:46:19	188.113.189.20	Admin	1
283353633	02.10.2025 00:08:26	46.42.178.153	cool	1
283355798	02.10.2025 01:35:57	188.113.189.20	Admin	1
283374482	02.10.2025 14:04:35	188.113.169.185	Toxa65	4
283374483	02.10.2025 14:04:35	188.113.169.185	Toxa65	4
283376189	02.10.2025 15:20:59	178.130.143.104	Amodey	1
283391239	03.10.2025 00:09:28	46.42.144.19	Qwerti	1
283395403	03.10.2025 04:04:39	188.113.189.20	Admin	1
283430302	04.10.2025 02:08:05	46.42.150.122	Qwerti	1
283433796	04.10.2025 06:07:57	188.113.173.21	Admin	1
283434180	04.10.2025 06:19:49	213.230.125.110	root	1
283455994	04.10.2025 18:24:16	185.100.102.205	artelit	1
283469655	05.10.2025 06:35:32	46.42.179.234	Qwerti	1
283471757	05.10.2025 07:45:59	91.105.156.2	Ram	1
283486523	05.10.2025 14:30:02	188.113.169.185	Toxa65	1
283499285	06.10.2025 00:11:11	46.42.149.205	cool	1
283537934	07.10.2025 00:16:14	46.42.150.220	cool	1
283575305	08.10.2025 00:11:59	46.42.150.161	cool	1
283613286	09.10.2025 00:10:39	46.42.151.210	cool	1
283617730	09.10.2025 04:21:58	92.37.142.244	Drag0	1
283637653	09.10.2025 15:54:19	92.50.219.20	sion	1
283637708	09.10.2025 15:56:11	92.50.219.20	sion	4
283715924	11.10.2025 12:16:38	145.255.22.191	zed888	1
TXT;

$service = new VoteService();
$result = $service->processVotesFromContent($content);

echo "Processed: ".$result['processed']."\n";
if (!empty($result['skipped'])) echo "Skipped as duplicates: ".$result['skipped']."\n";
if (!empty($result['errors'])) {
    echo "Errors:\n";
    foreach ($result['errors'] as $e) echo "- $e\n";
}

// Выведем топ для наглядности
require_once __DIR__ . '/../src/models/VoteTop.php';
$vt = new VoteTop(DatabaseConnection::getSiteConnection(), DatabaseConnection::getAuthConnection());
$top = $vt->getTopVoters(20);
echo "\nTop voters (this month):\n";
foreach ($top as $i => $row) {
    $n = $i+1;
    echo "$n. {$row['username']} (#{$row['account_id']}): votes={$row['vote_count']}, coins={$row['total_coins']}, last={$row['last_vote']}\n";
}
