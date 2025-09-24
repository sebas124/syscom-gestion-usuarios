@extends('layouts.app')

@section('content')
    <div class="mt-4">
        <h1>Registrar Usuario</h1>
    </div> 

    <form method="POST" action="{{ route('users.store') }}" onsubmit="saveSignature()" >
        @csrf
        <div class="mb-3">
            <label>Nombre</label>
            <input name="nombre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Correo</label>
            <input name="correo_electronico" type="email" class="form-control" required>
            
            @error('correo_electronico')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label>Cargo</label>
            <select name="id_rol" class="form-control" required>
                @foreach($roles as $r)
                    <option value="{{ $r->id }}">{{ $r->nombre_cargo }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Fecha de Ingreso</label>
            <input name="fecha_ingreso" type="date" class="form-control" max="{{ now()->toDateString() }}" required>
        </div>

        <div class="mb-3">
            <label>Firma</label>
            <canvas id="signature-pad" class="border" width=400 height=150></canvas>
            <br>
            <button type="button" id="clear-btn" class="btn btn-secondary btn-sm mt-2">Limpiar</button>
        </div>
        <input type="hidden" name="firma" id="firma-input">
        <button class="btn btn-success" type="submit">Guardar</button>
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);
        document.getElementById('clear-btn').addEventListener('click', ()=> signaturePad.clear());

        function saveSignature(){
            if(signaturePad.isEmpty()){
                alert('Por favor firmar antes de enviar');
                event.preventDefault();
                return false;
            }
            const dataUrl = signaturePad.toDataURL('image/png');
            document.getElementById('firma-input').value = dataUrl;
            return true;
        }
    </script>
@endpush
