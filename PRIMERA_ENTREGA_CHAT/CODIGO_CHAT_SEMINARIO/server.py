import asyncio
import websockets

# Clave almacenada en el servidor
key = 'sixteen byte key'

class Canal:
    def __init__(self):
        self.conexiones = set()

    async def agregar_cliente(self, websocket):
        self.conexiones.add(websocket)
        # Cuando un cliente se conecta, el servidor comparte la clave
        await websocket.send(f"CLAVE:{key}")

    async def eliminar_cliente(self, websocket):
        self.conexiones.remove(websocket)

    async def difundir(self, mensaje, emisor):
        for cliente in self.conexiones:
            if cliente != emisor:
                await cliente.send(mensaje)

async def handler(websocket, path, canal):
    await canal.agregar_cliente(websocket)
    try:
        async for message in websocket:
            # Retransmite el mensaje tal como lo recibe
            await canal.difundir(message, websocket)
    finally:
        await canal.eliminar_cliente(websocket)

# Iniciar el servidor WebSocket
canal = Canal()
start_server = websockets.serve(lambda ws, path: handler(ws, path, canal), "localhost", 8765)

asyncio.get_event_loop().run_until_complete(start_server)
asyncio.get_event_loop().run_forever()

#VERSION 8
# LOGIN Y REGISTER

