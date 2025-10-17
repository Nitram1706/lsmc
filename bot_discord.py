import discord
import mysql.connector
from datetime import datetime

# --- CONFIGURATION À MODIFIER ---
TOKEN = 'MTQyODQzNTgxMzk0MjgyNTExMQ.GXrR5N.qcUZpo_iwaVhw5iyZzj-4TeF7LSNW5_4ct2Qns'  # Colle le token de ton bot ici
DISPATCH_CHANNEL_ID = [
    1427324325031313598,
    1428440599719317685,
    1428442406310907904,
    1428442425000595718,
    1428442437805670550,
    1428442456369791087
]
    # Clique droit sur ton salon vocal "Dispatch" > "Copier l'ID"

# Configuration de la base de données
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': 'root', # 'root' pour MAMP, '' pour XAMPP
    'database': 'lsmc_db'
}
# ---------------------------------

intents = discord.Intents.default()
intents.voice_states = True
intents.members = True
client = discord.Client(intents=intents)

def get_db_connection():
    try:
        conn = mysql.connector.connect(**db_config)
        print("Connexion à la base de données RÉUSSIE.")
        return conn
    except mysql.connector.Error as e:
        print(f"ERREUR DE CONNEXION À LA BASE DE DONNÉES : {e}")
        return None

@client.event
async def on_ready():
    print(f'Bot de service connecté en tant que {client.user}')

@client.event
async def on_voice_state_update(member, before, after):
    if before.channel is None and after.channel is not None and after.channel.id in DISPATCH_CHANNEL_IDS:
        print(f"{member.display_name} a pris son service.")
        conn = get_db_connection()
        if conn:
            cursor = conn.cursor()
            sql = "INSERT INTO service_logs (user_discord_id, user_discord_name, join_time) VALUES (%s, %s, %s)"
            val = (str(member.id), member.display_name, datetime.now())
            cursor.execute(sql, val)
            conn.commit()
            print("Heure d'arrivée enregistrée.")
            cursor.close()
            conn.close()

    elif before.channel is not None and after.channel is None and before.channel.id in DISPATCH_CHANNEL_IDS:
        print(f"{member.display_name} a terminé son service.")
        conn = get_db_connection()
        if conn:
            cursor = conn.cursor()
            # ... (le reste de la logique de mise à jour) ...
            print("Heure de départ enregistrée.")
            cursor.close()
            conn.close()

client.run(TOKEN)