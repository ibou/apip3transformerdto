<?php

namespace App\Enum;

use App\Contract\Labelized;
use App\Trait\Enum\FromLabelTrait;

enum StatusEffect: string implements Labelized
{
    use FromLabelTrait;

    // Ailment
    case POISON = 'poison';
    case PARALYSIS = 'paralysis';
    case SLEEP = 'sleep';
    case STUN = 'stun';
    case BLAST = 'blast';
    case EXHAUST = 'exhaust';

    // Element Blight
    case FIREBLIGHT = 'fireblight';
    case WATERBLIGHT = 'waterblight';
    case ICEBLIGHT = 'iceblight';
    case THUNDERBLIGHT = 'thunderblight';
    case DRAGONBLIGHT = 'dragonblight';

    // Character Stat Boosts
    case HEALTH_RECOVERY_S = 'Health Recovery (S)';
    case STUN_NEGATED = 'Stun Negated';
    case DIVINE_PROTECTION = 'Divine Protection';
    case DEFENSE_UP = 'Defense Up';
    case ATTACK_UP = 'Attack Up';
    case HEALTH_RECOVERY_S_ANTIDOTE = 'Health Recovery (S) + Antidote';
    case SHARPNESS_REGENERATION = 'Sharpness Regeneration';
    case EARPLUGS_L = 'Earplugs (L)';
    case SHARPNESS_LOSS_REDUCED = 'Sharpness Loss Reduced';
    case BLIGHT_NEGATED = 'Blight Negated';
    case ATTACK_DEFENSE_UP = 'Attack and Defense Up';
    case STAMINA_USE_REDUCED = 'Stamina Use Reduced';
    case HEALTH_RECOVERY_L = 'Health Recovery (L)';
    case KNOCKBACKS_NEGATED = 'Knockbacks Negated';
    case SHARPNESS_EXTENSION = 'Sharpness Extension';
    case ELEMENTAL_ATTACK_BOOST = 'Elemental Attack Boost';
    case ENVIRONMENT_DAMAGE_NEGATED = 'Environment Damage Negated';
    case TREMORS_NEGATED = 'Tremors Negated';
    case SONIC_BARRIER = 'Sonic Barrier';
    case EARPLUGS_S = 'Earplugs (S)';
    case SONIC_WAVE = 'Sonic Wave';
    case STAMINA_RECOVERY_UP = 'Stamina Recovery Up';
    case AFFINITY_UP = 'Affinity Up';
    case HEALTH_REGENERATION = 'Health Regeneration';
    case WIND_PRESSURE_NEGATED = 'Wind Pressure Negated';
    case ATTACK_AFFINITY_UP = 'Attack and Affinity Up';

    public function label(): string
    {
        return match ($this) {
            // Ailment
            self::POISON => 'Poison',
            self::PARALYSIS => 'Paralysis',
            self::SLEEP => 'Sleep',
            self::STUN => 'Stun',
            self::BLAST => 'Blast',
            self::EXHAUST => 'Exhaust',

            // Element Blight
            self::FIREBLIGHT => 'Fireblight',
            self::WATERBLIGHT => 'Waterblight',
            self::ICEBLIGHT => 'Iceblight',
            self::THUNDERBLIGHT => 'Thunderblight',
            self::DRAGONBLIGHT => 'Dragonblight',

            // Character Stat Boosts
            self::HEALTH_RECOVERY_S => 'Health Recovery (S)',
            self::STUN_NEGATED => 'Stun Negated',
            self::DIVINE_PROTECTION => 'Divine Protection',
            self::DEFENSE_UP => 'Defense Up',
            self::ATTACK_UP => 'Attack Up',
            self::HEALTH_RECOVERY_S_ANTIDOTE => 'Health Recovery (S) + Antidote',
            self::SHARPNESS_REGENERATION => 'Sharpness Regeneration',
            self::EARPLUGS_L => 'Earplugs (L)',
            self::SHARPNESS_LOSS_REDUCED => 'Sharpness Loss Reduced',
            self::BLIGHT_NEGATED => 'Blight Negated',
            self::ATTACK_DEFENSE_UP => 'Attack and Defense Up',
            self::STAMINA_USE_REDUCED => 'Stamina Use Reduced',
            self::HEALTH_RECOVERY_L => 'Health Recovery (L)',
            self::KNOCKBACKS_NEGATED => 'Knockbacks Negated',
            self::SHARPNESS_EXTENSION => 'Sharpness Extension',
            self::ELEMENTAL_ATTACK_BOOST => 'Elemental Attack Boost',
            self::ENVIRONMENT_DAMAGE_NEGATED => 'Environment Damage Negated',
            self::TREMORS_NEGATED => 'Tremors Negated',
            self::SONIC_BARRIER => 'Sonic Barrier',
            self::EARPLUGS_S => 'Earplugs (S)',
            self::SONIC_WAVE => 'Sonic Wave',
            self::STAMINA_RECOVERY_UP => 'Stamina Recovery Up',
            self::AFFINITY_UP => 'Affinity Up',
            self::HEALTH_REGENERATION => 'Health Regeneration',
            self::WIND_PRESSURE_NEGATED => 'Wind Pressure Negated',
            self::ATTACK_AFFINITY_UP => 'Attack and Affinity Up',
        };
    }

    /**
     * @return StatusEffect[]
     */
    public static function elementsBlightsCases(): array
    {
        return [
            self::FIREBLIGHT,
            self::WATERBLIGHT,
            self::ICEBLIGHT,
            self::THUNDERBLIGHT,
            self::DRAGONBLIGHT,
        ];
    }

    /**
     * @return StatusEffect[]
     */
    public static function ailmentsCases(): array
    {
        return [
            self::POISON,
            self::PARALYSIS,
            self::SLEEP,
            self::STUN,
            self::BLAST,
            self::EXHAUST,
        ];
    }

    /**
     * @return StatusEffect[]
     */
    public static function characterStatBoosts(): array
    {
        return [
            self::HEALTH_RECOVERY_S,
            self::STUN_NEGATED,
            self::DIVINE_PROTECTION,
            self::DEFENSE_UP,
            self::ATTACK_UP,
            self::HEALTH_RECOVERY_S_ANTIDOTE,
            self::SHARPNESS_REGENERATION,
            self::EARPLUGS_L,
            self::SHARPNESS_LOSS_REDUCED,
            self::BLIGHT_NEGATED,
            self::ATTACK_DEFENSE_UP,
            self::STAMINA_USE_REDUCED,
            self::HEALTH_RECOVERY_L,
            self::KNOCKBACKS_NEGATED,
            self::SHARPNESS_EXTENSION,
            self::ELEMENTAL_ATTACK_BOOST,
            self::ENVIRONMENT_DAMAGE_NEGATED,
            self::TREMORS_NEGATED,
            self::SONIC_BARRIER,
            self::EARPLUGS_S,
            self::SONIC_WAVE,
            self::STAMINA_RECOVERY_UP,
            self::AFFINITY_UP,
            self::HEALTH_REGENERATION,
            self::WIND_PRESSURE_NEGATED,
            self::ATTACK_AFFINITY_UP,
        ];
    }

    /**
     * @return StatusEffect[]
     */
    public static function weaponStatusesCases(): array
    {
        return \array_merge(self::elementsBlightsCases(), self::ailmentsCases());
    }

    /**
     * @return StatusEffect[]
     */
    public static function armorStatusesCases(): array
    {
        return self::elementsBlightsCases();
    }

    /**
     * @return StatusEffect[]
     */
    public static function monstersAilmentsCases(): array
    {
        return \array_merge(self::elementsBlightsCases(), self::ailmentsCases());
    }

    public static function tryFromValue(int $value): ?self
    {
        return match ($value) {
            1 => self::FIREBLIGHT,
            2 => self::WATERBLIGHT,
            3 => self::THUNDERBLIGHT,
            4 => self::ICEBLIGHT,
            5 => self::DRAGONBLIGHT,
            6 => self::POISON,
            7 => self::SLEEP,
            8 => self::PARALYSIS,
            9 => self::BLAST,
            default => null
        };
    }
}
