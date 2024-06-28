<template>
    <nav class="navbar is-dark">
        <div class="navbar-brand">
            <router-link to="/" class="navbar-item">
                <strong>ILO CRM</strong>
            </router-link>
        </div>
    </nav>

    <div class="navbar-menu">
        <div class="navbar-end">
            <div class="navbar-item">
                <div class="buttons">
                    <router-link to="/sign-up" class="button is-success">Sign up</router-link>
                    <router-link to="/log-in" class="button is-light">Log in</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup></script>