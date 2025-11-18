import serial
import requests
import time

# --- CONFIGURAÇÃO DE COMUNICAÇÃO ---
# ⚠️ IMPORTANTE: Ajuste a porta COM para a porta do seu Arduino (ex: 'COM3' no Windows, '/dev/ttyACM0' no Linux)
SERIAL_PORT = 'COM3' 
BAUD_RATE = 9600

# ⚠️ IMPORTANTE: Ajuste o caminho para o seu script PHP que recebe os dados.
PHP_RECEIVER_URL = 'http://localhost/SMI-WEB/php/receiver.php'

# --- CONFIGURAÇÃO DO PROJETO ---
# O Arduino Central irá simular o envio de dados para todas as máquinas.
# Usaremos 3 IDs de máquina como exemplo. O Python enviará dados alternados para eles.
MAQUINA_IDS = Faça uma query com todas as maquinas do banco de dados
maquina_index = 0

# --- INICIALIZAÇÃO DA COMUNICAÇÃO SERIAL ---
try:
    ser = serial.Serial(SERIAL_PORT, BAUD_RATE, timeout=1)
    print(f"✅ Conexão Serial estabelecida em {SERIAL_PORT}.")
    time.sleep(2) # Espera o Arduino inicializar (saída 'SMI: Ready')
    ser.flushInput() # Limpa o buffer de entrada
except serial.SerialException as e:
    print(f"❌ Erro ao abrir a porta serial {SERIAL_PORT}: {e}")
    print("Certifique-se de que o Arduino está conectado e o Serial Monitor está fechado.")
    exit()

def send_data_to_php(data_payload):
    """Envia os dados recebidos da Serial para o script PHP via HTTP GET."""
    try:
        response = requests.get(PHP_RECEIVER_URL, params=data_payload, timeout=5)
        
        # Verifica se a resposta foi bem-sucedida (código 200)
        if response.status_code == 200:
            print(f"  -> PHP OK: {response.text.strip()}")
        else:
            print(f"  -> PHP ERRO ({response.status_code}): {response.text.strip()}")

    except requests.exceptions.RequestException as e:
        print(f"  -> ERRO HTTP: Falha ao conectar ao PHP/Localhost: {e}")

# --- LOOP PRINCIPAL DE LEITURA ---
while True:
    try:
        if ser.in_waiting > 0:
            line = ser.readline().decode('utf-8').strip() # Lê e limpa a linha (ex: "500,25.5,45.0,150")
            
            if line and line.count(',') == 3: # Garante que temos 4 valores (3 vírgulas)
                
                # Divide a string em 4 variáveis
                gas, temp, umid, vibra = line.split(',')
                
                # Determina o ID da Máquina que estamos atualizando neste ciclo
                fk_id = MAQUINA_IDS[maquina_index]

                print(f"--- Lendo para Máquina ID: {fk_id} ---")
                print(f"Serial Data: Gás={gas}, Temp={temp}, Umid={umid}")

                # 💡 A LÓGICA MAIS CRÍTICA: Distribuição de Dados
                # É aqui que você define quais sensores se aplicam a cada máquina simulada,
                # de acordo com o seu exemplo (A, B, C).
                
                # PAYLOAD A SER ENVIADO PARA O PHP
                payload = {
                    'fk_id_maquina': fk_id,
                    'temperatura': float(temp),
                    'umidade': float(umid),
                    'gas': int(gas),
                }
                
                # Você pode aplicar lógica para enviar dados diferentes:
                if fk_id == 2: # Ex: Máquina B só mede Temp e Umidade
                    # Zera o dado de Gás e Vibração para esta Máquina no DB
                    payload['gas'] = 0
                
                elif fk_id == 3: # Ex: Máquina C só mede Gás e Vibração
                    # Zera o dado de Temp e Umidade para esta Máquina no DB
                    payload['temperatura'] = 0.0
                    payload['umidade'] = 0.0

                send_data_to_php(payload)
                
                # Avança para o próximo ID de máquina (ciclo)
                maquina_index = (maquina_index + 1) % len(MAQUINA_IDS)

            elif 'SMI: Ready' not in line: # Ignora o sinal inicial de pronto do Arduino
                 print(f"⚠️ Formato Serial Inválido ou Incompleto: '{line}'")

    except Exception as e:
        print(f"❌ Ocorreu um erro inesperado: {e}")
        time.sleep(5)