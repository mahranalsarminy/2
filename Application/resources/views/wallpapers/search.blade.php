@extends('layouts.front')
@section('title', 'Search Results: ' . $query)
@section('content')
    {!! ads_home_page_top() !!}
    
    <!-- Header -->
    <header class="header my-5">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class