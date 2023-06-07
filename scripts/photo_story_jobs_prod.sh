#!/usr/bin/env bash
wget -w 3 -O - https://newstrack.com/cron/photo-story-backup-crawling?cron_type=1
sleep 5
wget -w 3 -O - https://newstrack.com/cron/photo-story-backup-crawling?cron_type=2
sleep 5
wget -w 3 -O - https://newstrack.com/cron/photo-story-backup-crawling?cron_type=3