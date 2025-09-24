<!doctype html>
<html>
    <head><meta charset="utf-8"></head>
    <body>
        <h2>Contrato de Trabajo</h2>
        <p>Certifica que la persona <strong>{{ $usuario->nombre }}</strong>, con el correo electronico <strong>{{ $usuario->correo_electronico }}</strong>,
            presta los servicios como rol de <strong>{{ $usuario->rol->nombre_cargo ?? '' }}</strong> desde la fecha <strong>{{ $usuario->fecha_ingreso }}</strong>.</p>

        <p>Firma:</p>
        <div>
            <img src="data:image/png;base64,{{ $usuario->firma }}" style="width:300px;"/>
        </div>
    </body>
</html>
