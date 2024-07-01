<template>
    <div class="dbviewset">
        
        <div class="columns is-multiline is-mobile">
            <div class="column is-full">
                <h1 class="title">Basic Table</h1>
            </div>
            <!--remember add a page for uploading or exporting item-->
            <div class="column is-half">
                <div class="buttons">
                    <router-link to="/management/upload" class="button is-primary">
                        Upload
                    </router-link>
                    <router-link to="/management/export" class="button is-primary">
                        Export
                    </router-link>
                </div>
            </div>

            <div class="column is-half">
                <div class="buttons">
                    <router-link to="/modify" class="button is-primary is-outlined">
                        Add
                    </router-link>
                    <button class="button is-danger is-outlined">
                        Delete
                    </button>
                </div>
            </div>

            <div class="column is-half">
                <!--在这里写一个dropdown下拉列表用于选择查询字段/表头；
                search需要发送对应的信息给后端接口（或许需要在组件里添加）
                -->
                <input
                    class="input is-primary"
                    type="text"
                    placeholder="Please input"
                />
            </div>
                
            <div class="column is-half">
                <div class="buttons">
                    <button class="button is-primary">
                        Search
                    </button>
                    <button class="button">
                        Reset
                    </button>
                </div>
            </div>
            

            <div class="column is-full">
                <div class="table-container">
                   
                    <CombinedTable />
                                    
                </div>

                <nav class="pagination" role="navigation" aria-label="pagination">
                    <a href="#" class="pagination-previous">Previous</a>
                    <a href="#" class="pagination-next">Next page</a>
                    <ul class="pagination-list">
                        <li>
                        <a href="#" class="pagination-link" aria-label="Goto page 1">1</a>
                        </li>
                        <li>
                        <span class="pagination-ellipsis">&hellip;</span>
                        </li>
                        <li>
                        <a href="#" class="pagination-link" aria-label="Goto page 5">5</a>
                        </li>
                        <li>
                        <a
                            class="pagination-link is-current"
                            aria-label="Page 6"
                            aria-current="page"
                            >6</a
                        >
                        </li>
                        <li>
                        <a href="#" class="pagination-link" aria-label="Goto page 7">7</a>
                        </li>
                        <li>
                        <span class="pagination-ellipsis">&hellip;</span>
                        </li>
                        <li>
                        <a href="#" class="pagination-link" aria-label="Goto page 15">15</a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</template>

<script setup>
import CombinedTable from '@/components/table/CombinedTable.vue'


</script>
