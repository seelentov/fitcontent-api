#!/bin/bash

functions=(
'dcps() { sudo docker compose ps; }'
 'dcup() { sudo docker compose up "$1"; }'
 'dcdestroy() { sudo docker compose down "$1" --rmi all --volumes --remove-orphans; }'
 'dcdown() { sudo docker compose down "$1"; }'
 'dclogs() { sudo docker compose logs "$1"; }'
 'dcwatch() { sudo docker compose logs "$1" --follow; }'
 'dcrestart() { sudo docker compose restart "$1"; }'
)

for func in "${functions[@]}"; do
 echo "$func" >> ~/.bashrc || {
  echo "Ошибка при добавлении функции $func в $term" >&2
  exit 1
 }
done

echo "Функции успешно добавлены в $term. Не забудьте перезагрузить вашу оболочку (например, source ~/.bashrc)."
