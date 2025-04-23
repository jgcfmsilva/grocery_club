@extends('layouts.app')

@section('title', 'Confirmation Email Sent')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
@endpush

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-body p-5">
                    <!-- Mensagem que será exibida -->
                    <div class="alert alert-success text-center" role="alert">
                        <h4 class="alert-heading">Email de verificação enviado!</h4>
                        <p>
                            Acabamos de enviar um e-mail de verificação para o seu endereço de e-mail. 
                            Por favor, verifique a sua caixa de entrada e siga as instruções no e-mail para 
                            confirmar o seu endereço de e-mail.
                        </p>
                        <hr>
                        <p class="mb-0">
                            Se não receber o e-mail em alguns minutos, verifique também a pasta de spam 
                            ou clique no botão abaixo para re-enviar o e-mail de verificação.
                        </p>
                    </div>

                    <!-- Formulário para reenvio de e-mail -->
                    <div class="d-flex justify-content-center mt-8">
                        <form method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary rounded-2">
                                <i class="fa-solid fa-repeat mr-2"></i> Reenviar E-mail
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection