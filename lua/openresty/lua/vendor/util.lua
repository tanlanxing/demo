local _M = {}

local print_struct
print_struct = function(value,  prefix)
    prefix = prefix or "    "
    if type(value) == "table" then
        local str = "Table("
        for key, val in pairs(value) do
            if type(val) == "table" then
                str = string.format("%s\n%s%s %s %s", str, prefix, key, "=>", print_struct(val, prefix.."   "))
            elseif type(val) == "string" or type(val) == "number" then
                str = string.format("%s\n%s%s => (%s)%s", str, prefix, key, type(val), val)
            elseif type(val) == "boolean" then
                if val then
                    str = string.format("%s\n%s%s => (boolean)true", str, prefix, key)
                else
                    str = string.format("%s\n%s%s => (boolean)false", str, prefix, key)
                end
            else
                str = string.format("%s\n%s%s => (type)%s", str, prefix, key, type(val))
            end
        end
        return string.format("%s\n%s)", str, prefix:sub(5))
    elseif type(value) == "string" or type(value) == "number" then
        return string.format("(%s)%s", type(value), value)
    elseif type(value) == "boolean" then
        if value then
            return "(boolean)true"
        else
            return "(boolean)false"
        end
    else
        return string.format("(type)%s", type(value))
    end
end

_M.print_struct = print_struct

return _M
