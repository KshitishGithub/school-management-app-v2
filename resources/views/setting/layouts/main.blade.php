@extends('layouts.master')
@section('content')
    @php
        define('PAGE', 'admin_setting');
    @endphp
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col">
                        <h3 class="page-title">Settings</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.setting') }}">Settings</a></li>
                            <li class="breadcrumb-item active">{{ PAGE_BREADCRUMB }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="settings-menu-links">
                <ul class="nav nav-tabs menu-tabs">
                    @if (Auth::user()->role == '4')
                        <li class="nav-item {{ INNER_PAGE == 'general_setting' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.setting') }}">General Settings</a>
                        </li>
                        <li class="nav-item {{ INNER_PAGE == 'configure' ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('admin.configure') }}">Configure</a>
                        </li>
                    @endif
                    <li class="nav-item {{ INNER_PAGE == 'banner' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.setting.banner') }}">Banner</a>
                    </li>
                    <li class="nav-item {{ INNER_PAGE == 'notice' ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('admin.notice') }}">Notice</a>
                    </li>
                </ul>
            </div>
            @yield('setting_content')
        </div>
    </div>
@endsection
