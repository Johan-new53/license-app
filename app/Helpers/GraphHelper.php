<?php

if (!function_exists('getGraphConfig')) {
    function getGraphConfig()
    {
        $tenantKey = auth()->user()->graph_tenant ?? null;

        return $tenantKey
            ? config("graph.tenants.$tenantKey")
            : null;
    }
}