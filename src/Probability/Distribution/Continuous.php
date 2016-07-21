<?php
namespace Math\Probability\Distribution;

use Math\Probability\Combinatorics;
use Math\Statistics\RandomVariable;
use Math\Functions\Special;

class Continuous
{
    /**
     * Continuous uniform distribution - Interval
     * Computes the probability of a specific interval within the continuous uniform distribution.
     * https://en.wikipedia.org/wiki/Uniform_distribution_(continuous)
     *
     * x₂ − x₁
     * -------
     *  b − a
     *
     * @param number $a lower boundary of the distribution
     * @param number $b upper boundary of the distribution
     * @param number $x₁ lower boundary of the probability interval
     * @param number $x₂ upper boundary of the probability interval
     *
     * @return number probability of specific interval
     */
    public static function uniformInterval(float $a, float $b, float $x₁, float $x₂)
    {
        if (( $x₁ < $a || $x₁ > $b ) || ( $x₂ < $a || $x₂ > $b )) {
            throw new \Exception('x values are outside of the distribution.');
        }
        return ( $x₂ - $x₁ ) / ( $b - $a );
    }

    /**
     * Logistic distribution - probability density function
     *
     * https://en.wikipedia.org/wiki/Logistic_distribution
     *
     *                     /  x - μ \
     *                 exp| - -----  |
     *                     \    s   /
     * f(x; μ, s) = -----------------------
     *                /        /  x - μ \ \ ²
     *              s| 1 + exp| - -----  | |
     *                \        \    s   / /
     *
     * @param number $μ location parameter
     * @param number $s scale parameter > 0
     * @param number $x
     *
     * @return float
     */
    public static function logisticPDF($μ, $s, $x)
    {
        if ($s <= 0) {
            throw new \Exception('Scale parameter s must be > 0');
        }

        $ℯ＾⁻⁽x⁻μ⁾／s = exp(-($x - $μ) / $s);

        return $ℯ＾⁻⁽x⁻μ⁾／s / ($s * pow(1 + $ℯ＾⁻⁽x⁻μ⁾／s, 2));
    }

    /**
     * Logistic distribution - cumulative distribution function
     * From -∞ to x (lower CDF)
     * https://en.wikipedia.org/wiki/Logistic_distribution
     *
     *                      1
     * f(x; μ, s) = -------------------
     *                      /  x - μ \ 
     *              1 + exp| - -----  |
     *                      \    s   /
     *
     * @param number $μ location parameter
     * @param number $s scale parameter
     * @param number $x
     *
     * @return float
     */
    public static function logisticCDF($μ, $s, $x)
    {
        if ($s <= 0) {
            throw new \Exception('Scale parameter s must be > 0');
        }

        $ℯ＾⁻⁽x⁻μ⁾／s = exp(-($x - $μ) / $s);

        return 1 / (1 + $ℯ＾⁻⁽x⁻μ⁾／s);
    }

    /**
     * Log-logistic distribution - probability density function
     * Also known as the Fisk distribution.
     * https://en.wikipedia.org/wiki/Log-logistic_distribution
     *
     *              (β/α)(x/α)ᵝ⁻¹
     * f(x; α, β) = -------------
     *              (1 + (x/α)ᵝ)²
     *
     * @param number $α scale parameter (α > 0)
     * @param number $β shape parameter (β > 0)
     * @param number $x (x > 0)
     */
    public static function logLogisticPDF($α, $β, $x)
    {
        if ($α <= 0 || $β <= 0 || $x <= 0) {
            throw new \Exception('All parameters must be > 0');
        }

        $⟮β／α⟯⟮x／α⟯ᵝ⁻¹  = ($β / $α) * pow($x / $α, $β - 1);
        $⟮1 ＋ ⟮x／α⟯ᵝ⟯² = pow(1 + ($x / $α)**$β, 2);

        return $⟮β／α⟯⟮x／α⟯ᵝ⁻¹ / $⟮1 ＋ ⟮x／α⟯ᵝ⟯²;
    }

    /**
     * Log-logistic distribution - cumulative distribution function
     * Also known as the Fisk distribution.
     * https://en.wikipedia.org/wiki/Log-logistic_distribution
     *
     *                   1
     * F(x; α, β) = -----------
     *              1 + (x/α)⁻ᵝ
     *
     * @param number $α scale parameter (α > 0)
     * @param number $β shape parameter (β > 0)
     * @param number $x (x > 0)
     */
    public static function logLogisticCDF($α, $β, $x)
    {
        if ($α <= 0 || $β <= 0 || $x <= 0) {
            throw new \Exception('All parameters must be > 0');
        }

        $⟮x／α⟯⁻ᵝ = pow($x / $α, -$β);

        return 1 / (1 + $⟮x／α⟯⁻ᵝ);
    }

    /**
     * Beta distribution - probability density function
     * https://en.wikipedia.org/wiki/Beta_distribution
     *
     *       xᵃ⁻¹(1 - x)ᵝ⁻¹
     * pdf = --------------
     *           B(α,β)
     *
     * @param number $α shape parameter α > 0
     * @param number $β shape parameter β > 0
     * @param number $x x ∈ (0,1)
     *
     * @return float
     */
    public static function betaPDF($α, $β, $x): float
    {
        if ($α <= 0 || $β <= 0) {
            throw new \Exception('α and β must be > 0');
        }
        if ($x < 0 || $x > 1) {
            throw new \Exception('x must be between 0 and 1');
        }

        $xᵃ⁻¹     = pow($x, $α - 1);
        $⟮1 − x⟯ᵝ⁻¹ = pow(1 - $x, $β - 1);
        $B⟮α、β⟯    = Special::beta($α, $β);

        return ($xᵃ⁻¹ * $⟮1 − x⟯ᵝ⁻¹) / $B⟮α、β⟯;
    }
}
