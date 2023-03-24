#!/bin/bash

APP_DIR=$(dirname -- "$0")
SHORTCUT_START=~/.local/share/applications/monitor-start.desktop
SHORTCUT_STOP=~/.local/share/applications/monitor-stop.desktop

echo "[Desktop Entry]" >> $SHORTCUT_START
echo "Type=Application" >> $SHORTCUT_START
echo "Terminal=true" >> $SHORTCUT_START
echo "Name=Monitor START" >> $SHORTCUT_START
echo "Exec=$APP_DIR/start.sh" >> $SHORTCUT_START

echo "[Desktop Entry]" >> $SHORTCUT_RUN
echo "Type=Application" >> $SHORTCUT_RUN
echo "Terminal=true" >> $SHORTCUT_RUN
echo "Name=Monitor STOP" >> $SHORTCUT_RUN
echo "Exec=$APP_DIR/stop.sh" >> $SHORTCUT_RUN
