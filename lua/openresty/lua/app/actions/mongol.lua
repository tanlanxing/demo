local share = require("share")
--write to cache
local arg = ngx.req.get_uri_args()
local id=tonumber(arg["id"])

local succ, err=share.incr_to_cache("state_"..id, 1)
if succ then
    ngx.say('{"status":0}')
else
    ngx.say('{"status":2, "message":"'..err..'"}')
end