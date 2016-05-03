local _M = {}

local util = require("util")

local cache_ngx = ngx.shared.my_cache

function _M.get_keys()
    -- body
    local keys = cache_ngx:get_keys(0)
    return keys
end

function _M.get_from_cache(key)
    local value = cache_ngx:get(key)
    return value
end

function _M.set_to_cache(key, value, exptime)
    if not exptime then
        exptime = 0
    end

    local succ, err, forcible = cache_ngx:set(key, value, exptime)
    return succ, err
end

function _M.incr_to_cache(key, value) 
    local val = cache_ngx:get(key)
    local succ, err
    if val then
        succ, err = cache_ngx:incr(key, value)
    else
        succ, err = _M.set_to_cache(key, 1)
    end
    return succ, err
end

return _M