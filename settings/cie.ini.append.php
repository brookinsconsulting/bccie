<?php /* #?ini charset="utf8"?

[CieSettings]
# Cronjob debug output
Debug=disabled

# Log File
Log=var/log/cie.log

# Log Debug Output (Reserved but not used)
LogDebug=disabled

# Export Directory
Directory=var/export

# Export only records created in the last n of days
# ExportLimitedRange=disabled
ExportLimitedRange=disabled

# Number of days in the past to export. Must enable, 'ExportLimitedRange' to use
DateRangeToExport=7

# Remove records after export (Required but not implemented)
# RemoveExported=enabled
RemoveExported=disabled

# Collections to export, Array of IDs
Collection[]
# Collection[]=4242
# Collection[]=2442
# Collection[]=2424

# Collection attributes to exclude, Array of IDs
ExcludeAttributeID[]
# ExcludeAttributeID[]=001
# ExcludeAttributeID[]=002
# ExcludeAttributeID[]=003

# Csv Export Format
CsvFormat=csv

# Csv Export Separator
CsvSeparator=;
# CsvSeparator=|
# CsvSeparator=,
# CsvSeparator=:
# CsvSeparator=#

# Sylk Export Format
SylkFormat=sylk

# Sylk Export Separator
SylkSeparator=;

# Optional setting to enable export of 0 instead
# of empty string when attribute value is empty
EmptyAttributeExport=enabled

*/ ?>