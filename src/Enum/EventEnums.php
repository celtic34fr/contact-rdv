<?php

namespace Celtic34fr\ContactCore\Enum;

enum EventEnums: string
{
    case ContactTel     = 'CTEL';   // demande de rendez-vous via demande de contact
    case CongesAbs      = 'ABS';    // congé, absence
    case Intervention   = 'INTR';   // intervention (chez client, dans locaux ...)
    case Meeting        = "MEET";   // réunion de travail, meeting divers ...

    public function _toString(): string
    {
        return (string) $this->value;
    }

    public static function isValid(string $value): bool
    {
        $courrielValuesTab = array_column(self::cases(), 'value');
        return in_array($value, $courrielValuesTab);
    }

    public static function getCases(): array
    {
        $cases = self::cases();
        return array_map(static fn (\UnitEnum $case) => $case->name, $cases);
    }

    public static function getValues(): array
    {
        $cases = self::cases();
        return array_map(static fn (\UnitEnum $case) => $case->value, $cases);
    }

    public static function getValuesCases(): array
    {
        return array_combine(self::getValues(), self::getCases());
    }
}
