<template>
    <nav class="navbar is-dark" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">           

            <router-link to="/about" class="navbar-item">
                <strong>ILO CRM</strong>
            </router-link>

            <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbarBasic" class="navbar-menu">
            <div class="navbar-start">

                <div class="navbar-item has-dropdown is-hoverable">

                    <router-link to="/" class="navbar-link">Home</router-link>

                    <div class="navbar-dropdown">
                        <router-link to="/master-database" class="navbar-item">MasterDB</router-link>
       
                        <router-link to="/mentoring" class="navbar-item">Mentoring</router-link>
                        <router-link to="/year-in-industry" class="navbar-item">YearinIndustry</router-link>
                        <router-link to="/projects"  class="navbar-item">Projects</router-link>
                    
                        <hr class="navbar-divider">
                        <router-link to="/dashboard/my-account"  class="navbar-item">Account</router-link>
                    </div>
                </div>
                
            </div>
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