local printTable
printTable = function (table,  prefix)
    prefix = prefix or ""
    for key, val in pairs(table) do
        if type(val) == "table" then
            ngx.say(prefix..key.."=>")        
            printTable(val, prefix.."   ")
        elseif type(val) == "string" or type(val) == "number" then
            ngx.say(prefix..key.." => "..val)
        elseif type(val) == "boolean" then
            if val then
                ngx.say(prefix..key.." => (boolean)true")
            else
                ngx.say(prefix..key.." => (boolean)false")
            end
        else
            ngx.say(prefix..key.." => (type)"..type(val))
        end
    end
end

local mongo = require "resty.mongol"

ngx.say("connect to db")
local conn = mongo:new()
conn:set_timeout(1000)
local ok, err = conn:connect("192.168.174.129", 27019)
if not ok then
    ngx.say("connect failed: "..err)
    return
end
printTable(conn)

ngx.say("\njudgement is master")
local bool, hosts = conn:ismaster()
if bool then
    ngx.say("Master")
else
    ngx.say("Not master")
end
printTable(hosts)

ngx.say("\nget primary server")
local newconn = conn:getprimary ()
printTable(newconn)

ngx.say("\ncomplete exmple")
local db = conn:new_db_handle ( "test" )
local col = db:get_col("test")
local docs = {
    {
        name="connan", 
        age=28,
        gender="male"
    }
}
ngx.say(#docs)
local n, err = col:insert(docs, 1, 1)
if n == nil then
    ngx.say("insert failed:"..err)
end
local r = col:find_one({name="connan"})
if type(r) == "table" then
    ngx.say(r["name"])
else 
    ngx.say(type(r))
end

ngx.say("\nshow dbs")
local databases = newconn:databases ( )
printTable(databases)

ngx.say("\nselect db")
local db = newconn:new_db_handle("test")
printTable(db)

