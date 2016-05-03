local delay = 5
local handler
handler = function (premature)
    -- do some routine job in Lua just like a cron job
    ngx.log(ngx.ERR, "curr:", os.date("%x %X"))
    if premature then
        return
    end
    local ok, err = ngx.timer.at(delay, handler)
    if not ok then
        ngx.log(ngx.ERR, "failed to create the timer: ", err)
        return
    end
end

-- ngx.log(ngx.ERR, "today: ", ngx.today())
-- ngx.log(ngx.ERR, "time: ", ngx.time())
-- ngx.log(ngx.ERR, "utctime: ", ngx.utctime())
-- ngx.log(ngx.ERR, "localtime: ", ngx.localtime())
-- ngx.log(ngx.ERR, "now: ", ngx.now())
-- ngx.log(ngx.ERR, "http_time: ", ngx.http_time(ngx.now()))
-- ngx.log(ngx.ERR, "cookie_time: ", ngx.cookie_time(ngx.now()))

local ok, err = ngx.timer.at(delay, handler)
if not ok then
    ngx.log(ngx.ERR, "failed to create the timer: ", err)
    return
end