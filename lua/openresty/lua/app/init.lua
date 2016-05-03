local share = require ("share")
local mongo = require "resty.mongol"

local pull_to_db = function ( )
    local cache_keys = share:get_keys()

    if #cache_keys < 1 then
        return
    end

    local conn = mongo:new()
    conn:set_timeout(1000)
    local ok, err = conn:connect("192.168.174.129", 27019)
    if not ok then
        ngx.log(ngx.ERR, "connect failed: ", err)
        return
    end
    -- connect primary server
    local bool, hosts = conn:ismaster()
    if not bool then
        conn = conn:getprimary ()
    end

    local db = conn:new_db_handle ( "test" )
    local col = db:get_col("test")

    for __, key in ipairs(cache_keys) do
        local state = share.get_from_cache(key)
        share.set_to_cache(key, nil)

        local id = key:sub(7)
        local condition = { ["id"] = id }
        -- if exist
        local r = col:find_one(condition)
        if r then
            local doc = {
                ["$set"] = {state=r["state"]+state}
            }
            local n, err = col:update(condition, doc)
            if not n then
                share.inct_to_cache(key, state)
                ngx.log(ngx.ERR, "update error: ",err)
            end
        else
            local doc = {
                {
                    id= condition["id"],
                    state=state
                }
            }
            local n, err = col:insert(doc)
            if not n then
                share.inct_to_cache(key, state)
                ngx.log(ngx.ERR, "insert error: ", err)
            end
        end
    end

    local ok, err = conn:set_keepalive(10000, 100)
    if not ok then
        ngx.log(ngx.ERR, "failed to set keepalive: ", err)
        return
    end
end

--crontab job
local delay = 5
 local handler
 handler = function (premature)
     pull_to_db()
     if premature then
         return
     end
     local ok, err = ngx.timer.at(delay, handler)
     if not ok then
         ngx.log(ngx.ERR, "failed to create the timer: ", err)
         return
     end
 end

 local ok, err = ngx.timer.at(delay, handler)
 if not ok then
     ngx.log(ngx.ERR, "failed to create the timer: ", err)
     return
 end